Sub create_t()
    Dim w1 As Worksheet
    Set w1 = Worksheets("P_data")
    Dim total As Integer '出場校の総数
    Dim east As Integer '東日本の大学の総数
    Dim west As Integer '西日本の大学の総数
    '男子の参加校をP_dataから取得する
    total = w1.Cells(3, 15).Value '大学の総数
    total = 50
    east = w1.Cells(3, 13).Value '東日本大学の総数
    west = w1.Cells(3, 14).Value '西日本大学の総数
    'シード設定に必要な数値を算出
    Dim branch As Integer '上位のノードの数
    Dim preDepth As Integer
    preDepth = Int(Log(total) / Log(2#))
    branch = 2 ^ preDepth
    Dim numSeed As Integer
    numSeed = 2 * branch - total 'シード枠の総数

    'フラグの設定
    Dim seedFlag() As Variant '出場校のシードフラグ（シード校かどうかbool値）
    Dim preFlag() As Variant '上位ノードのシードフラグ（シード校を下位ノードに含むかのbool値）
    Dim i As Integer '深さ1のFor用
    Dim k As Integer '深さ2のFor用
    Dim o As Integer '深さ3のFor用
    Dim p As Integer '深さ4のFor用
    ReDim seedFlag(total)
    ReDim preFlag(branch)
    'シード値の初期化
    For i = 0 To total
        seedFlag(i) = 0 '0で初期化
    Next i
    For i = 0 To branch - 1
        preFlag(i) = 0 '0で初期化
    Next i
    'シードの決定
    If numSeed = 1 Then
        preFlag(0) = 1
    ElseIf numSeed > 1 Then
        Dim seedIndex As Variant
        Dim easyFpow As Integer 'シード権のないチームの試合が終わった時に残っているチーム数
        Dim Twidth As Integer  'トーナメントに残っているシード枠を保持。
        Dim remain As Integer 'シード枠の残数（シード校以外にも割り振りため）。この値が０になるまで繰り返し、シード枠に割り振る。
        seedIndex = Log(numSeed) / Log(2#) 'シード枠の数を2の累乗数にするための指数
        seedIndex = Int(seedIndex)
        easyFpow = 2 ^ seedIndex 'シード枠を2累乗で簡略化（シード校の総数が10の時の8）
        Twidth = branch / easyFpow 'シードを割り振るブロックの大きさ
        remain = numSeed - easyFpow '割り振ることのできるシード枠のあまり
        For i = 0 To easyFpow - 1
            preFlag(i * Twidth) = 1
        Next i
        If remain > 0 Then
            Dim cd As Integer '端数調整
            Dim temporary As Integer '(一時保持)
            cd = Twidth / 2
            temporary = cd
            While remain > 0
                seedIndex = Log(remain) / Log(2#)
                seedIndex = Int(seedIndex)
                easyFpow = 2 ^ seedIndex
                Twidth = branch / easyFpow
                remain = remain - easyFpow
                For i = 0 To easyFpow - 1
                    If preFlag(i * Twidth + temporary) = 1 Then '重複例外処理
                        temporary = temporary + 2 * cd
                    End If
                    preFlag(i * Twidth + temporary) = 1
                Next i
                temporary = temporary + 2 * cd
            Wend
        End If
    End If
    'seedFlag()にboolenを代入する
    Dim j As Integer
    j = 0
    For i = 0 To branch - 1
        If j < total + 1 Then
            If preFlag(i) = 0 Then
                seedFlag(j) = 0
                seedFlag(j + 1) = 0
                j = j + 2
            Else
                seedFlag(j) = 1
                j = j + 1
            End If
        End If

    Next i

'------------------------------------------------------------------------------------ここから表作成--------------------------------------------------------------------------------------------------------------------
    Cells.ClearFormats 'トーナメントを初期化
    Cells.ClearContents
    Dim pair() As Variant 'ペアを検出する
    ReDim pair(total)
    'pair() = 1 の時は、非シード校同士の結合
    'pair() = 2 の時は、シード校との結合
    For i = 0 To total - 1
        pair(i) = 0
    Next i
    seedFlag(total) = 2
    temporary = 0
    j = 0
    Dim NumMatch() As Integer '累積試合数で縦線を引くか決める
    Dim MatchVartical() As Integer '分岐点の縦方向の位置を決める
    Dim MatchBeside() As Integer  '分岐点の横方向の位置を決める
    Dim Count As Integer
    Dim Divide_Boder As Integer
    Dim reverse As Integer
    Dim back As Integer
    Dim Center As Integer
    ReDim NumMatch(total)
    ReDim MatchBeside(total)
    ReDim MatchVartical(total)
    Divide_Boder = Int(total / 2)
    reverse = (4 + preDepth) * 2
    back = Divide_Boder * 2
    Count = 0
    For i = 0 To total - 1
        NumMatch(i) = 0
        MatchBeside(i) = 5
    Next i
    'トーナメントの左端の初期状態の決定
    Dim hh As Integer
    hh = 2

    For i = 0 To total - 1
        If i < Divide_Boder Then
            Cells(2 + 2 * i, 2) = seedFlag(i)
            If seedFlag(i) = 0 Then
                Cells(2 + 2 * i, 3).Select
                With Selection.Borders(xlEdgeBottom)
                    .LineStyle = xlContinuous
                    .ColorIndex = 0
                    .TintAndShade = 0
                    .Weight = xlThin
                End With
                '実際の試合での試合順を数える
                temporary = temporary + 1
                Cells(2 + 2 * i, 1) = temporary
                If seedFlag(i + 1) = 0 And pair(i) = 0 And pair(i + 1) = 0 Then '非シード校の結合
                    Range(Cells(3 + 2 * i, 3), Cells(4 + 2 * i, 3)).Select
                    With Selection.Borders(xlEdgeRight)
                        .LineStyle = xlContinuous
                        .ColorIndex = 0
                        .TintAndShade = 0
                        .Weight = xlThin
                    End With
                    Cells(3 + 2 * i, 4).Select
                    With Selection.Borders(xlEdgeBottom)
                        .LineStyle = xlContinuous
                        .ColorIndex = 0
                        .TintAndShade = 0
                        .Weight = xlThin
                    End With
                    pair(i) = 1
                    pair(i + 1) = 1
                End If
            ElseIf seedFlag(i) = 1 Then
                Range(Cells(2 + 2 * i, 3), Cells(2 + 2 * i, 4)).Select
                With Selection.Borders(xlEdgeBottom)
                    .LineStyle = xlContinuous
                    .ColorIndex = 0
                    .TintAndShade = 0
                    .Weight = xlThin
                End With
                If seedFlag(i + 1) = 1 And pair(i) = 0 And pair(i + 1) = 0 Then 'シード校同士の結合
                    Range(Cells(3 + 2 * i, 4), Cells(4 + 2 * i, 4)).Select
                    With Selection.Borders(xlEdgeRight)
                        .LineStyle = xlContinuous
                        .ColorIndex = 0
                        .TintAndShade = 0
                        .Weight = xlThin
                    End With
                    Cells(3 + 2 * i, 5).Select
                    With Selection.Borders(xlEdgeBottom)
                        .LineStyle = xlContinuous
                        .ColorIndex = 0
                        .TintAndShade = 0
                        .Weight = xlThin
                    End With
                    pair(i) = 2
                    pair(i + 1) = 2
                ElseIf seedFlag(i + 1) = 0 And seedFlag(i + 2) = 0 And pair(i) = 0 And pair(i + 1) = 0 And pair(i + 2) = 0 Then
                    Range(Cells(3 + 2 * i, 4), Cells(5 + 2 * i, 4)).Select 'シード校と非シード校の結合
                    With Selection.Borders(xlEdgeRight)
                        .LineStyle = xlContinuous
                        .ColorIndex = 0
                        .TintAndShade = 0
                        .Weight = xlThin
                    End With
                    Range(Cells(5 + i * 2, 3), Cells(6 + i * 2, 3)).Select
                    With Selection.Borders(xlEdgeRight)
                        .LineStyle = xlContinuous
                        .ColorIndex = 0
                        .TintAndShade = 0
                        .Weight = xlThin
                    End With
                    Cells(5 + i * 2, 4).Select
                    With Selection.Borders(xlEdgeBottom)
                        .LineStyle = xlContinuous
                        .ColorIndex = 0
                        .TintAndShade = 0
                        .Weight = xlThin
                    End With
                    Cells(4 + 2 * i, 5).Select
                    With Selection.Borders(xlEdgeBottom)
                        .LineStyle = xlContinuous
                        .ColorIndex = 0
                        .TintAndShade = 0
                        .Weight = xlThin
                    End With
                    pair(i) = 2
                    pair(i + 1) = 2
                    pair(i + 2) = 2
                End If
                '実際の試合での試合順を数える
                j = j + 1
                Cells(2 + 2 * i, 1) = j + (total - numSeed)
            End If
        ElseIf i >= Divide_Boder Then
            Cells(2 + 2 * i - back, reverse - 2) = seedFlag(i)
            If seedFlag(i) = 0 Then
                Cells(2 + 2 * i - back, reverse - 3).Select
                With Selection.Borders(xlEdgeBottom)
                    .LineStyle = xlContinuous
                    .ColorIndex = 0
                    .TintAndShade = 0
                    .Weight = xlThin
                End With
                '実際の試合での試合順を数える
                temporary = temporary + 1
                Cells(2 + 2 * i - back, reverse - 1) = temporary
                If seedFlag(i + 1) = 0 And pair(i) = 0 And pair(i + 1) = 0 Then '非シード校の結合
                    Range(Cells(3 + 2 * i - back, reverse - 3), Cells(4 + 2 * i - back, reverse - 3)).Select
                    With Selection.Borders(xlEdgeLeft)
                        .LineStyle = xlContinuous
                        .ColorIndex = 0
                        .TintAndShade = 0
                        .Weight = xlThin
                    End With
                    Cells(3 + 2 * i - back, reverse - 4).Select
                    With Selection.Borders(xlEdgeBottom)
                        .LineStyle = xlContinuous
                        .ColorIndex = 0
                        .TintAndShade = 0
                        .Weight = xlThin
                    End With
                    pair(i) = 1
                    pair(i + 1) = 1
                End If
            ElseIf seedFlag(i) = 1 Then
                Range(Cells(2 + 2 * i - back, reverse - 3), Cells(2 + 2 * i - back, reverse - 4)).Select
                With Selection.Borders(xlEdgeBottom)
                    .LineStyle = xlContinuous
                    .ColorIndex = 0
                    .TintAndShade = 0
                    .Weight = xlThin
                End With
                If seedFlag(i + 1) = 1 And pair(i) = 0 And pair(i + 1) = 0 Then 'シード校同士の結合
                    Range(Cells(3 + 2 * i - back, reverse - 4), Cells(4 + 2 * i - back, reverse - 4)).Select
                    With Selection.Borders(xlEdgeLeft)
                        .LineStyle = xlContinuous
                        .ColorIndex = 0
                        .TintAndShade = 0
                        .Weight = xlThin
                    End With
                    Cells(3 + 2 * i - back, reverse - 5).Select
                    With Selection.Borders(xlEdgeBottom)
                        .LineStyle = xlContinuous
                        .ColorIndex = 0
                        .TintAndShade = 0
                        .Weight = xlThin
                    End With
                    MatchVartical(Count) = 1 + 2 * i
                    NumMatch(i) = 1
                    NumMatch(i + 1) = 1
                    Count = Count + 1
                    pair(i) = 2
                    pair(i + 1) = 2
                ElseIf seedFlag(i + 1) = 0 And seedFlag(i + 2) = 0 And pair(i) = 0 And pair(i + 1) = 0 And pair(i + 2) = 0 Then
                    Range(Cells(3 + 2 * i - back, reverse - 4), Cells(5 + 2 * i - back, reverse - 4)).Select 'シード校と非シード校の結合
                    With Selection.Borders(xlEdgeLeft)
                        .LineStyle = xlContinuous
                        .ColorIndex = 0
                        .TintAndShade = 0
                        .Weight = xlThin
                    End With
                    Range(Cells(5 + i * 2 - back, reverse - 3), Cells(6 + i * 2 - back, reverse - 3)).Select
                    With Selection.Borders(xlEdgeLeft)
                        .LineStyle = xlContinuous
                        .ColorIndex = 0
                        .TintAndShade = 0
                        .Weight = xlThin
                    End With
                    Cells(5 + i * 2 - back, reverse - 4).Select
                    With Selection.Borders(xlEdgeBottom)
                        .LineStyle = xlContinuous
                        .ColorIndex = 0
                        .TintAndShade = 0
                        .Weight = xlThin
                    End With
                    Cells(4 + 2 * i - back, reverse - 5).Select
                    With Selection.Borders(xlEdgeBottom)
                        .LineStyle = xlContinuous
                        .ColorIndex = 0
                        .TintAndShade = 0
                        .Weight = xlThin
                    End With
                    pair(i) = 2
                    pair(i + 1) = 2
                    pair(i + 2) = 2
                End If
                '実際の試合での試合順を数える
                j = j + 1
                Cells(2 + 2 * i - back, reverse - 1) = j + (total - numSeed)
            End If
        End If
    Next i
    For i = 0 To total - 4
        If i < Divide_Boder Then
            If pair(i) = 1 And pair(i + 1) = 1 And pair(i + 2) = 1 And pair(i + 3) = 1 Then
                Range(Cells(4 + 2 * i, 4), Cells(7 + 2 * i, 4)).Select
                With Selection.Borders(xlEdgeRight)
                    .LineStyle = xlContinuous
                    .ColorIndex = 0
                    .TintAndShade = 0
                    .Weight = xlThin
                End With
                Cells(5 + i * 2, 5).Select
                With Selection.Borders(xlEdgeBottom)
                    .LineStyle = xlContinuous
                    .ColorIndex = 0
                    .TintAndShade = 0
                    .Weight = xlThin
                End With
                pair(i) = 2
                pair(i + 1) = 2
                pair(i + 2) = 2
                pair(i + 3) = 2
            End If
        ElseIf i >= Divide_Boder Then
            If pair(i) = 1 And pair(i + 1) = 1 And pair(i + 2) = 1 And pair(i + 3) = 1 Then
                Range(Cells(4 + 2 * i - back, reverse - 4), Cells(7 + 2 * i - back, reverse - 4)).Select
                With Selection.Borders(xlEdgeLeft)
                    .LineStyle = xlContinuous
                    .ColorIndex = 0
                    .TintAndShade = 0
                    .Weight = xlThin
                End With
                Cells(5 + i * 2 - back, reverse - 5).Select
                With Selection.Borders(xlEdgeBottom)
                    .LineStyle = xlContinuous
                    .ColorIndex = 0
                    .TintAndShade = 0
                    .Weight = xlThin
                End With
                pair(i) = 2
                pair(i + 1) = 2
                pair(i + 2) = 2
                pair(i + 3) = 2
            End If
        End If
    Next i
    hh = 4
    For i = 5 To (preDepth + 1) * 2
        For j = 1 To 1 + total * 2
            Cells(j, i).Select
            If Cells(j, i).Borders(xlEdgeBottom).LineStyle = xlContinuous Then
                MatchVartical(Count) = j
                MatchBeside(Count) = i
                Count = Count + 1
            End If
        Next j
    Next i
    For i = 0 To Count
        pair(i) = 2
    Next i
    Divide_Boder = Count / 2
    For j = 2 To preDepth + 2
        For i = 0 To Count
            If i < Divide_Boder Then
                If pair(i) = j And pair(i + 1) = j Then
                    Range(Cells(MatchVartical(i), MatchBeside(i)), Cells(MatchVartical(i + 1), MatchBeside(i + 1))).Select
                    With Selection.Borders(xlEdgeRight)
                        .LineStyle = xlContinuous
                        .ColorIndex = 0
                        .TintAndShade = 0
                        .Weight = xlThin
                    End With
                    Center = MatchVartical(i) + MatchVartical(i + 1)
                    Center = Int(Center / 2)
                    Cells(Center, MatchBeside(i) + 1).Select
                    With Selection.Borders(xlEdgeBottom)
                        .LineStyle = xlContinuous
                        .ColorIndex = 0
                        .TintAndShade = 0
                        .Weight = xlThin
                    End With
                    MatchVartical(i / 2) = Center
                    MatchBeside(i / 2) = MatchBeside(i) + 1
                    pair(i) = j
                    pair(i + 1) = j
                End If
            ElseIf i >= Divide_Boder Then
            End If
        Next i
    Next j
End Sub

